package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Product {

    JSONObject jsonObject;

    public static final String jsonPattern = "{\"product\":{\"name\":\"%s\",\"units\":\"%s\",\"vat\":\"0\",\"purchasePrice\":\"%s\"," +
            "\"barcode\":\"%s\",\"sku\":\"%s\",\"vendorCountry\":\"Тестовая страна\",\"vendor\":\"Тестовый производитель\",\"info\":\"\"}}";

    public Product(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }
}
