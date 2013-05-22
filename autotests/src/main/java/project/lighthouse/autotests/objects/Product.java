package project.lighthouse.autotests.objects;

import org.json.JSONObject;

public class Product {

    JSONObject productJsonObject;

    public static final String productJsonDataPattern = "{\"product\":{\"name\":\"%s\",\"units\":\"%s\",\"vat\":\"0\",\"purchasePrice\":\"%s\",\"barcode\":\"%s\",\"sku\":\"%s\",\"vendorCountry\":\"Тестовая страна\",\"vendor\":\"Тестовый производитель\",\"info\":\"\"}}";

    public Product(JSONObject productJsonObject) {
        this.productJsonObject = productJsonObject;
    }
}
