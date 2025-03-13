import sys
from PIL import Image
import os

def compress_image(input_path, output_path, quality=85):
    try:
        # Open the image
        with Image.open(input_path) as img:
            # Convert to RGB if needed
            if img.mode in ('RGBA', 'P'):
                img = img.convert('RGB')
            
            # Get original format
            file_format = img.format
            
            # Save compressed image
            img.save(output_path, 
                    format=file_format,
                    quality=quality,
                    optimize=True)
            
            return True
    except Exception as e:
        print(f"Error: {str(e)}")
        return False

if __name__ == "__main__":
    if len(sys.argv) != 3:
        print("Usage: python compress.py input_path output_path")
        sys.exit(1)
    
    input_path = sys.argv[1]
    output_path = sys.argv[2]
    
    if compress_image(input_path, output_path):
        print("Image compressed successfully")
    else:
        print("Failed to compress image")