package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.objects.api.product.ExtraBarcode;

import java.util.ArrayList;
import java.util.List;

public class CustomVariableStorage {

    private List<ExtraBarcode> extraBarcodes = new ArrayList<>();

    private String email;

    public List<ExtraBarcode> getExtraBarcodes() {
        return extraBarcodes;
    }

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }
}
