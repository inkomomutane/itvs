#!/usr/bin/env python3
import re
import os
import json
from pathlib import Path

def extract_translation_keys():
    keys = set()
    js_path = Path("resources/js")
    
    # Pattern to match $t('...') and t('...')
    pattern = r"(?:\$t|(?<![a-zA-Z_])t)\(['\"`]([^'\"`]*)['\"`]\)"
    
    for file_path in js_path.rglob("*.vue"):
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                matches = re.findall(pattern, content)
                keys.update(matches)
        except Exception as e:
            print(f"Error processing {file_path}: {e}")
    
    for file_path in js_path.rglob("*.ts"):
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                matches = re.findall(pattern, content)
                keys.update(matches)
        except Exception as e:
            print(f"Error processing {file_path}: {e}")
    
    for file_path in js_path.rglob("*.js"):
        try:
            with open(file_path, 'r', encoding='utf-8') as f:
                content = f.read()
                matches = re.findall(pattern, content)
                keys.update(matches)
        except Exception as e:
            print(f"Error processing {file_path}: {e}")
    
    return sorted(keys)

def create_portuguese_translations(keys):
    """Create Portuguese translations for the keys"""
    translations = {
        # Personal information
        'Name': 'Nome',
        'Second name': 'Segundo nome',
        'Last name': 'Sobrenome',
        'Enter your name': 'Digite seu nome',
        'Birth date': 'Data de nascimento',
        'Place of birth': 'Local de nascimento',
        'Nationality': 'Nacionalidade',
        'Sex': 'Sexo',
        'Marital status': 'Estado civil',
        'Patent': 'Patente',
        'Language': 'Idioma',
        'email': 'e-mail',
        
        # Documents
        'ID Number': 'Número do documento',
        'ID Emitted At': 'Documento emitido em',
        'ID expires at': 'Documento expira em',
        'Passport Number': 'Número do passaporte',
        'Passport Emitted At': 'Passaporte emitido em',
        'Passport expires at': 'Passaporte expira em',
        'Driver license': 'Carteira de motorista',
        'Driver license Emitted At': 'Carteira emitida em',
        'Driver license emitted At': 'Carteira emitida em',
        'Driver license expires at': 'Carteira expira em',
        'License expires at': 'Licença expira em',
        'Número de identificação policial (NIP)': 'Número de identificação policial (NIP)',
        'Senha': 'Senha',
        
        # Education
        'Education': 'Formação',
        'Formações': 'Formações',
        'School': 'Escola',
        'Degree': 'Grau',
        'Field of study': 'Área de estudo',
        'Grade': 'Nota',
        'Activities': 'Atividades',
        'Description': 'Descrição',
        'Add education': 'Adicionar formação',
        'Editar formação': 'Editar formação',
        'Excluir formação': 'Excluir formação',
        
        # Experience
        'Experience': 'Experiência',
        'Experiences': 'Experiências',
        'Title': 'Título',
        'Company': 'Empresa',
        'Employment type': 'Tipo de emprego',
        'Employment Type': 'Tipo de emprego',
        'Location': 'Localização',
        'Add experience': 'Adicionar experiência',
        'Employee': 'Funcionário',
        
        # Dates
        'Start date': 'Data de início',
        'End date': 'Data de fim',
        'Ingress data': 'Data de ingresso',
        'Present': 'Presente',
        'Select Date': 'Selecionar data',
        
        # Disciplinary Records
        'Disciplinary Records': 'Registros disciplinares',
        'Disciplinary Record Details': 'Detalhes do registro disciplinar',
        'Infraction': 'Infração',
        'Penalty': 'Penalidade',
        'Organ': 'Órgão',
        'Process Number': 'Número do processo',
        'Record Date': 'Data do registro',
        
        # Member Management
        'Add member': 'Adicionar membro',
        'Edit member': 'Editar membro',
        'Member': 'Membro',
        'Members': 'Membros',
        'Member card': 'Cartão de membro',
        'Police card': 'Cartão policial',
        
        # General Actions
        'Add': 'Adicionar',
        'Save': 'Salvar',
        'Cancel': 'Cancelar',
        'Delete': 'Excluir',
        'Close': 'Fechar',
        'Search': 'Pesquisar',
        'Update Profile': 'Atualizar perfil',
        'Profile': 'Perfil',
        'Actions': 'Ações',
        'Create Sheet': 'Criar planilha',
        
        # System
        'Dashboard': 'Painel',
        'Log In': 'Entrar',
        'Confirm password': 'Confirmar senha',
        'SISTEMA DE GESTÃO DE MEMBROS': 'SISTEMA DE GESTÃO DE MEMBROS',
        'settings management': 'gerenciamento de configurações',
        
        # Presence
        'Presence': 'Presença',
        'Presence Register': 'Registro de presença',
        
        # Days
        'Dia 1': 'Dia 1',
        'Dia 2': 'Dia 2',
        'Dia 3': 'Dia 3',
        'Dia 4': 'Dia 4',
        'Dia 5': 'Dia 5',
        'Dia 6': 'Dia 6',
        'Dia 7': 'Dia 7',
        'Dia 8': 'Dia 8',
        'Dia 9': 'Dia 9',
        'Dia 10': 'Dia 10',
        'Dia 11': 'Dia 11',
        'Dia 12': 'Dia 12',
        'Dia 13': 'Dia 13',
        'Dia 14': 'Dia 14',
        'Dia 15': 'Dia 15',
        'Dia 16': 'Dia 16',
        'Dia 17': 'Dia 17',
        'Dia 18': 'Dia 18',
        'Dia 19': 'Dia 19',
        'Dia 20': 'Dia 20',
        'Dia 21': 'Dia 21',
        'Dia 22': 'Dia 22',
        'Dia 23': 'Dia 23',
        'Dia 24': 'Dia 24',
        'Dia 25': 'Dia 25',
        'Dia 26': 'Dia 26',
        'Dia 27': 'Dia 27',
        'Dia 28': 'Dia 28',
        'Dia 29': 'Dia 29',
        'Dia 30': 'Dia 30',
        'Dia 31': 'Dia 31',
        
        # Misc
        'NIP': 'NIP',
        'Institution': 'Instituição',
        'of': 'de',
        'Showing': 'Mostrando',
        'Are you sure you want to delete this element?': 'Tem certeza de que deseja excluir este elemento?',
        'experience': 'experiência',
    }
    
    return translations

if __name__ == "__main__":
    keys = extract_translation_keys()
    print("Found translation keys:")
    for key in keys:
        print(f"  '{key}'")
    
    print(f"\nTotal keys found: {len(keys)}")
    
    # Create translations
    translations = create_portuguese_translations(keys)
    
    # Load existing pt.json
    pt_file = Path("lang/pt.json")
    if pt_file.exists():
        with open(pt_file, 'r', encoding='utf-8') as f:
            existing_translations = json.load(f)
    else:
        existing_translations = {}
    
    # Add new translations
    new_keys = []
    for key in keys:
        if key not in existing_translations:
            if key in translations:
                existing_translations[key] = translations[key]
                new_keys.append(key)
            else:
                # Add with placeholder if no translation provided
                existing_translations[key] = key  # Keep original as placeholder
                new_keys.append(key)
    
    # Sort the translations
    sorted_translations = dict(sorted(existing_translations.items()))
    
    # Write back to pt.json
    with open(pt_file, 'w', encoding='utf-8') as f:
        json.dump(sorted_translations, f, ensure_ascii=False, indent=4)
    
    print(f"\nAdded {len(new_keys)} new translations to pt.json:")
    for key in new_keys:
        print(f"  '{key}': '{sorted_translations[key]}'")
