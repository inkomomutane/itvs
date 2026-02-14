#!/usr/bin/env bash
set -euo pipefail

########################
# CONFIG
########################

APP_NAME="food"
IMAGE_NAME="food"
REGISTRY="localhost:5000"
DOCKERFILE_PATH="."
KEEP_IMAGES=2
REPLICAS=2
NETWORK_NAME="traefik_public"

IMAGE_TAG="1.0.0"
FULL_IMAGE="${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}"

########################
# FUNCTIONS
########################

log() {
  echo "[INFO] $1"
}

ensure_registry() {
  if ! docker ps --format '{{.Names}}' | grep -i '^registry$'; then
    log "Starting Docker Registry v3"
    docker run -d \
      --restart=always \
      --name registry \
      -p 5000:5000 \
      -v /opt/registry:/etc/docker/registry \
      -v registry_data:/var/lib/registry \
      registry:3
  else
    log "Registry already running"
  fi
}

build_and_push() {
  log "Building image ${FULL_IMAGE}"
  # get the latest tag from git
   if [ -d .git ]; then
     GIT_TAG=$(git describe --tags "$(git rev-list --tags --max-count=1)" 2>/dev/null || true)

        if [ -n "${GIT_TAG}" ]; then
          IMAGE_TAG="${GIT_TAG}"

         else
            IMAGE_TAG="$(date +%Y%m%d%H%M%S)"
        fi

     FULL_IMAGE="${REGISTRY}/${IMAGE_NAME}:${IMAGE_TAG}"
     log "Using image tag from git: ${IMAGE_TAG}"
   fi

  docker build -t "${FULL_IMAGE}" -t "${REGISTRY}/${IMAGE_NAME}:latest" "${DOCKERFILE_PATH}"
    log "Pushing image to registry"
    docker push "${FULL_IMAGE}"

    log "Image pushed: ${FULL_IMAGE}"

    docker push "${REGISTRY}/${IMAGE_NAME}:latest"

    log "Image pushed: ${REGISTRY}/${IMAGE_NAME}:latest"
}

cleanup_registry_images() {
  log "Cleaning old images (keeping ${KEEP_IMAGES})"

  TAGS=$(curl -sf http://localhost:5000/v2/${IMAGE_NAME}/tags/list \
    | jq -r '.tags[]' \
    | sort -r)

  COUNT=0
  for TAG in ${TAGS}; do
    COUNT=$((COUNT + 1))
    if [ "${COUNT}" -gt "${KEEP_IMAGES}" ]; then
      DIGEST=$(curl -sI \
        -H "Accept: application/vnd.oci.image.manifest.v1+json" \
        http://localhost:5000/v2/${IMAGE_NAME}/manifests/${TAG} \
        | awk '/Docker-Content-Digest/ {print $2}' \
        | tr -d $'\r')

      if [ -n "${DIGEST}" ]; then
        log "Deleting manifest ${TAG} (${DIGEST})"
        curl -sf -X DELETE \
          http://localhost:5000/v2/${IMAGE_NAME}/manifests/${DIGEST}
      fi
    fi
  done

  log "Running registry garbage collection"
 # docker exec registry registry garbage-collect /etc/docker/registry/config.yml
}

ensure_swarm() {
  if ! docker info --format '{{.Swarm.LocalNodeState}}' | grep -i active; then
    log "Initializing Docker Swarm"
    docker swarm init
  fi
}

ensure_network() {
  if ! docker network ls --format '{{.Name}}' | grep -i "^${NETWORK_NAME}$"; then
    docker network create \
      --driver overlay \
      --attachable \
      "${NETWORK_NAME}"
  fi
}

deploy_service() {
    # Check if all services exist
    SERVICES_EXIST=0
    for SERVICE_SUFFIX in "php" "workers"; do
      if docker service ls --format '{{.Name}}' | grep -i "^${APP_NAME}_${SERVICE_SUFFIX}$"; then
        ((SERVICES_EXIST++))
      fi
    done

    # If not all services exist, destroy and recreate
    if [ $SERVICES_EXIST -ne 2 ]; then
      log "Inconsistent state detected. Destroying and recreating stack..."
      docker stack rm ${APP_NAME}
      sleep 7  # Wait for services to be removed
      # ensure the tack is fully removed

      if docker service ls --format '{{.Name}}' | grep -i "^${APP_NAME}_"; then
        log "Waiting for services to be removed..."
        sleep 7
      fi

      docker stack deploy -c docker-compose.prod.yml ${APP_NAME} -d
      exit 0
    fi

    # Original loop for updates
    for SERVICE_SUFFIX in "php" "workers"; do
      SERVICE_NAME="${APP_NAME}_${SERVICE_SUFFIX}"
      log "Updating service ${SERVICE_NAME}"
      docker service update --image "${FULL_IMAGE}" "${SERVICE_NAME}"
    done
}

########################
# EXECUTION
########################

ensure_registry
build_and_push
cleanup_registry_images
ensure_swarm
ensure_network
deploy_service

log "Deployment finished successfully"
