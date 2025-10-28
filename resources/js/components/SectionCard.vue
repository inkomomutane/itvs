<script setup lang="ts">
 import { cn } from '@/lib/utils';
 import { Card, CardContent, CardFooter, CardHeader } from '@/components/ui/card';
</script>
<template>
     <Card :class="cn('rounded-xl w-full border',$attrs.class)">
          <div >
              <CardHeader class="font-semibold   flex flex-row justify-between items-center">
                  <div>
                      <slot name="header-left">
                          Experience
                      </slot>
                  </div>
                  <div>
                      <slot name="header-right">
                      </slot>
                  </div>
              </CardHeader>
          </div>
         <CardContent>
             <slot name="content"/>
         </CardContent>
          <CardFooter class="">
              <slot name="footer"></slot>
          </CardFooter>
     </Card>
</template>
