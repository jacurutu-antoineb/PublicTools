class JwtToken {
    constructor(id, token, name, endpoint) {
	this.tokenid = id;
        this.token = token;
        let parts = token.split('.');
        this.header = JSON.parse(atob(parts[0]));
        this.payload = JSON.parse(atob(parts[1]));
	this.friendlyname = name;
	this.endpoint = endpoint;
    }

    getId() {
	return this.tokenid;
    }

    getToken() {
	return this.token;
    }

    getApiEndpoint() {
	return this.endpoint;
    }

    getFriendlyName() {
	return this.friendlyname;
    }

    getAudience() {
        return this.payload.aud;
    }

    getExpiryDate() {
        return new Date(this.payload.exp);
    }

    getExpiryDateEpoch() {
	return this.payload.exp;
    }

    getName() {
        return this.payload.name;
    }

    isExpired() {
        return this.getExpiryDate() < new Date();
    }

    display() {
        return `URL: https://${this.getAudience()}.obsec.io\nCreator: ${this.getName()}\nExpires: ${this.isExpired() ? "Expired" : this.getExpiryDate()}`;
    }
}
