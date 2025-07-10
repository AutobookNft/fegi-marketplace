const algosdk = require('algosdk');
const bip39   = require('bip39');

const root24 = 'cd ..';

const words   = root24.trim().split(/\s+/);
const invalid = words.filter(w => !bip39.wordlists.english.includes(w));
if (invalid.length) {
  console.error('Parole non valide:', invalid);
  process.exit(1);
}

const mdk  = algosdk.mnemonicToMasterDerivationKey(root24);
const acct = algosdk.generateAccountFromMasterDerivationKey(mdk, 1);

console.log('Treasury mnemonic (25 parole):');
console.log(algosdk.secretKeyToMnemonic(acct.sk));
console.log('Address:', acct.addr);
