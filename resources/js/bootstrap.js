import axios from 'axios'
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest'

export const truncateAddress = (address) => {
    if (!address) return "No Account";
    const match = address.match(
        /^(0x[a-zA-Z0-9]{3})[a-zA-Z0-9]+([a-zA-Z0-9]{4})$/
    );
    if (!match) return address;
    return `${match[1]}…${match[2]}`;
};

export function bytestohex() {
    let bytes = window.crypto.getRandomValues(new Uint8Array(32)); 
    var hexstring='', h;
    for(var i=0; i<bytes.length; i++) {
        h=bytes[i].toString(16);
        if(h.length==1) { h='0'+h; }
        hexstring+=h;
    } 
    let msg = "Hi there! We just need you to sign this message to confirm that you have access to this wallet."
    return msg+ hexstring + Date.now();
}

export function paramsToObject(entries) {
    const result = {}
    for(const [key, value] of entries) { // each 'entry' is a [key, value] tupple
      result[key] = value;
    }
    return result;
}