import algosdk from "algosdk";

const account = algosdk.generateAccount();
const address = account.addr.toString();
const mnemonic25 = algosdk.secretKeyToMnemonic(account.sk);
const privateKeyHex = Buffer.from(account.sk).toString('hex'); // AGGIUNGI QUESTA RIGA

console.log("Address:", address);
console.log("Mnemonic (25 parole):", mnemonic25);
console.log("Private Key (HEX):", privateKeyHex); // AGGIUNGI QUESTA RIGA