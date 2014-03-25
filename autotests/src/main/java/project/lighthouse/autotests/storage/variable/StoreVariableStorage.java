package project.lighthouse.autotests.storage.variable;

public class StoreVariableStorage {

    private String storeNumber;
    private String address;
    private String contacts;

    public void setStoreNumber(String storeNumber) {
        this.storeNumber = storeNumber;
    }

    public void setAddress(String address) {
        this.address = address;
    }

    public void setContacts(String contacts) {
        this.contacts = contacts;
    }

    public String getStoreNumber() {
        return storeNumber;
    }

    public String getAddress() {
        return address;
    }

    public String getContacts() {
        return contacts;
    }
}
