#!/usr/bin/env python3
import json
from pathlib import Path

# New Portuguese translations
new_translations = {
    "Current Password": "Senha Atual",
    "Email": "E-mail",
    "Email address": "Endereço de e-mail",
    "Email password reset link": "Enviar link de redefinição de senha",
    "Email verification": "Verificação de e-mail",
    "Ensure your account is using a long, random password to stay secure": "Certifique-se de que sua conta está usando uma senha longa e aleatória para permanecer segura",
    "Enter your details below to create your account": "Digite seus dados abaixo para criar sua conta",
    "Enter your email to receive a password reset link": "Digite seu e-mail para receber um link de redefinição de senha",
    "Entrar": "Entrar",
    "Esqueceu senha?": "Esqueceu senha?",
    "Forgot password": "Esqueceu a senha",
    "Lembrar": "Lembrar",
    "Log in": "Entrar",
    "Log out": "Sair",
    "Marital Status": "Estado Civil",
    "New password": "Nova senha",
    "No items found": "Nenhum item encontrado",
    "Or, return to": "Ou, voltar para",
    "Password": "Senha",
    "Phone": "Telefone",
    "Please enter your new password below": "Digite sua nova senha abaixo",
    "Please verify your email address by clicking on the link we just emailed to you.": "Verifique seu endereço de e-mail clicando no link que acabamos de enviar por e-mail.",
    "Profile information": "Informações do perfil",
    "Profile settings": "Configurações do perfil",
    "Resend verification email": "Reenviar e-mail de verificação",
    "Reset password": "Redefinir senha",
    "Save password": "Salvar senha",
    "Saved": "Salvo",
    "Saved.": "Salvo.",
    "This is a secure area of the application. Please confirm your password before continuing.": "Esta é uma área segura da aplicação. Confirme sua senha antes de continuar.",
    "Update password": "Atualizar senha",
    "Update your name and email address": "Atualize seu nome e endereço de e-mail",
    "Verify email": "Verificar e-mail",
    "Your email address is unverified.": "Seu endereço de e-mail não foi verificado.",
    "log in": "entrar"
}

# Load existing pt.json
pt_file = Path("lang/pt.json")
with open(pt_file, 'r', encoding='utf-8') as f:
    translations = json.load(f)

# Update translations
for key, value in new_translations.items():
    if key in translations:
        translations[key] = value

# Write back to pt.json
with open(pt_file, 'w', encoding='utf-8') as f:
    json.dump(translations, f, ensure_ascii=False, indent=4)

print(f"Updated {len(new_translations)} translations in pt.json")
