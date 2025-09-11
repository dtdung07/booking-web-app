-- Cập nhật bảng coso để thêm cột AnhUrl nếu chưa có
ALTER TABLE `coso` ADD COLUMN IF NOT EXISTS `AnhUrl` VARCHAR(500) NULL DEFAULT NULL;

-- Cập nhật một số URL ảnh mẫu cho các cơ sở hiện có
UPDATE `coso` SET `AnhUrl` = 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/08/202308051004475343.webp' WHERE `MaCoSo` = 11;
UPDATE `coso` SET `AnhUrl` = 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/05/202305111648358011.webp' WHERE `MaCoSo` = 12;
UPDATE `coso` SET `AnhUrl` = 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/10/202310241151064241.webp' WHERE `MaCoSo` = 21;
UPDATE `coso` SET `AnhUrl` = 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/09/202309151401492644.webp' WHERE `MaCoSo` = 22;
UPDATE `coso` SET `AnhUrl` = 'https://storage.quannhautudo.com/data/thumb_800/Data/images/product/2023/07/202307251245217721.webp' WHERE `MaCoSo` = 23;
