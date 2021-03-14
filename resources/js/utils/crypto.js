import CryptoJS from "crypto-js";

export const encrypt = (key, data) => {
    const wordArray = CryptoJS.lib.WordArray.random(16);
    const parsedKey = CryptoJS.enc.Base64.parse(key);
    const encrypted = CryptoJS.AES.encrypt(data, parsedKey, {
        iv: wordArray,
        mode: CryptoJS.mode.CBC,
        padding: CryptoJS.pad.Pkcs7
    }).toString();

    const iv = CryptoJS.enc.Base64.stringify(wordArray);
    const result = {
        iv: iv,
        value: encrypted,
        mac: CryptoJS.HmacSHA256(iv + encrypted, parsedKey).toString()
    };

    return CryptoJS.enc.Base64.stringify(
        CryptoJS.enc.Utf8.parse(JSON.stringify(result))
    );
};
