# print("hello from python\n")
# print("hello again from python\n")

from paddleocr import PaddleOCR # main OCR dependencies
import argparse
import logging
logging.disable(logging.DEBUG)
logging.disable(logging.WARNING)

# Setup image path
parser  = argparse.ArgumentParser("ppocr_runner")
parser.add_argument("file_path", help="Path of file that needed to be processed", type=str)
p_clear_bm = parser.parse_args().file_path
# p_clear_bm = 'c:/xampp/htdocs/CodeIgniter3/uploads/clear_bm.png'

# Setup model
ocr_model = PaddleOCR(use_angle_cls=True, lang='en')

# Run the ocr method on the ocr model
result = ocr_model.ocr(p_clear_bm)

for res in result[0]:
    print(res[1][0])

