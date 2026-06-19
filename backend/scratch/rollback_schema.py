import os
import re

root_dir = r'c:\laragon\www\rental_project\app\Filament\Resources'

def process_file(file_path):
    with open(file_path, 'r', encoding='utf-8') as f:
        content = f.read()

    if 'Filament\\Schemas\\Schema' not in content:
        return

    print(f"Processing {file_path}")

    # Determine if it's a Form or Infolist
    is_infolist = 'Infolist' in os.path.basename(file_path) or 'infolist(' in content
    
    if is_infolist:
        # Replacements for Infolists
        content = content.replace('Filament\\Schemas\\Schema', 'Filament\\Infolists\\Infolist')
        content = content.replace('Filament\\Schemas\\Components\\Section', 'Filament\\Infolists\\Components\\Section')
        content = content.replace('Filament\\Schemas\\Components\\Grid', 'Filament\\Infolists\\Components\\Grid')
        content = content.replace('Filament\\Schemas\\Components\\Group', 'Filament\\Infolists\\Components\\Group')
        
        content = re.sub(r'configure\(Schema \$schema\): Schema', 'configure(Infolist $infolist): Infolist', content)
        content = re.sub(r'infolist\(Schema \$schema\): Schema', 'infolist(Infolist $infolist): Infolist', content)
        content = content.replace('$schema', '$infolist')
    else:
        # Replacements for Forms
        content = content.replace('Filament\\Schemas\\Schema', 'Filament\\Forms\\Form')
        content = content.replace('Filament\\Schemas\\Components\\Section', 'Filament\\Forms\\Components\\Section')
        content = content.replace('Filament\\Schemas\\Components\\Grid', 'Filament\\Forms\\Components\\Grid')
        content = content.replace('Filament\\Schemas\\Components\\Group', 'Filament\\Forms\\Components\\Group')
        content = content.replace('Filament\\Schemas\\Components\\Utilities\\Get', 'Filament\\Forms\\Get')
        content = content.replace('Filament\\Schemas\\Components\\Utilities\\Set', 'Filament\\Forms\\Set')
        
        content = re.sub(r'configure\(Schema \$schema\): Schema', 'configure(Form $form): Form', content)
        content = re.sub(r'form\(Schema \$schema\): Schema', 'form(Form $form): Form', content)
        content = content.replace('$schema', '$form')

    with open(file_path, 'w', encoding='utf-8') as f:
        f.write(content)

for root, dirs, files in os.walk(root_dir):
    for file in files:
        if file.endswith('.php'):
            process_file(os.path.join(root, file))
