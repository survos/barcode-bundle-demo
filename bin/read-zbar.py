import pyzbar
from PIL import Image
from pyzbar.pyzbar import decode

filename='../public/2-qr-codes.jpg';
im=Image.open(filename);

print(im.size)

decodedObjects = decode(im)
for obj in decodedObjects:
      print('Type : ', obj.type)
      print('Size : ', obj.rect)
      print('Data : ', obj.data,'\n')
