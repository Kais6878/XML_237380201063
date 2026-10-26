import xml.etree.ElementTree as ET

# Doc file Computer.xml
tree = ET.parse("Computer.xml")

# Lay nut goc
root = tree.getroot()

# Nhap ten may tinh can xoa
ten_can_xoa = input("Nhap ten may tinh can xoa: ")

# Tim va xoa may tinh
da_xoa = False

for computer in root.findall("computer"):
    name = computer.find("name")

    if name is not None and name.text == ten_can_xoa:
        root.remove(computer)
        da_xoa = True
        break

# Luu lai file XML neu xoa thanh cong
if da_xoa:
    tree.write(
        "Computer.xml",
        encoding="utf-8",
        xml_declaration=True
    )
    print("Da xoa may tinh thanh cong!")
else:
    print("Khong tim thay may tinh can xoa!")
