import os
import glob
import yaml
from transformers import AutoTokenizer

# Tải tokenizer BERT multilingual
tokenizer = AutoTokenizer.from_pretrained("bert-base-multilingual-cased")

# Đếm tokens theo tokenizer BERT
def count_bert_tokens(text):
    tokens = tokenizer.encode(text, add_special_tokens=True)
    return len(tokens)

# Kiểm tra responses trong một file
def check_responses_in_file(file_path, max_tokens=512):
    with open(file_path, 'r', encoding='utf-8') as file:
        try:
            domain_data = yaml.safe_load(file)
        except Exception as e:
            print(f"Lỗi khi đọc {file_path}: {e}")
            return []

    too_long = []
    responses = domain_data.get('responses', {})
    for utter_name, examples in responses.items():
        for i, example in enumerate(examples):
            text = example.get('text', '')
            tokens = count_bert_tokens(text)
            if tokens > max_tokens:
                too_long.append({
                    'file': file_path,
                    'utter': utter_name,
                    'index': i,
                    'tokens': tokens,
                    'text': text
                })
    return too_long

# Kiểm tra tất cả các file domain_*.yml
def check_all_domain_files(max_tokens=512):
    files = glob.glob("domain_*.yml")
    all_too_long = []

    for file_path in files:
        too_long = check_responses_in_file(file_path, max_tokens)
        all_too_long.extend(too_long)

    if all_too_long:
        print(f"\n🔴 Phát hiện {len(all_too_long)} câu trả lời vượt quá {max_tokens} tokens theo tokenizer BERT:")
        for item in all_too_long:
            print(f"\nFile: {item['file']}")
            print(f"Utter: {item['utter']} (index {item['index']}) - {item['tokens']} tokens")
            print(f"Text: {item['text'][:200]}{'...' if len(item['text']) > 200 else ''}")
    else:
        print(f"✅ Tất cả các câu trả lời đều <= {max_tokens} tokens theo tokenizer BERT.")

# Gọi hàm
check_all_domain_files()
