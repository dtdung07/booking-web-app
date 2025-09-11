-- Cập nhật một số món ăn để phân bổ vào các danh mục khác nhau
UPDATE monan SET MaDM = 20 WHERE MaMon IN (21, 22); -- Dê tươi
UPDATE monan SET MaDM = 21 WHERE MaMon IN (16, 17, 18, 19); -- Đồ uống  
UPDATE monan SET MaDM = 23 WHERE MaMon IN (5, 14, 23); -- Rau xanh
UPDATE monan SET MaDM = 25 WHERE MaMon IN (4, 13, 6); -- Hải sản
UPDATE monan SET MaDM = 22 WHERE MaMon IN (1, 22, 15); -- Thiết bản (raw dishes)
UPDATE monan SET MaDM = 24 WHERE MaMon IN (20, 9); -- Lẩu
UPDATE monan SET MaDM = 19 WHERE MaMon IN (2, 7, 8, 11, 12, 3, 10, 24); -- Món nhậu
