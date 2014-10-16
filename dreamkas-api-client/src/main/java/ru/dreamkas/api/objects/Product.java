package ru.dreamkas.api.objects;

import org.json.JSONException;
import org.json.JSONObject;
import ru.dreamkas.api.objects.abstraction.AbstractObject;

public class Product extends AbstractObject {

    private static final String API_URL = "/products";

    public Product(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public Product(String name,
                   String units,
                   String barcode,
                   String vat,
                   String purchasePrice,
                   String sellingPrice,
                   String groupId) throws JSONException {
        this(new JSONObject()
                        .put("type", "unit")
                        .put("name", name)
                        .put("units", units)
                        .put("barcode", barcode)
                        .put("vat", vat)
                        .put("purchasePrice", purchasePrice)
                        .put("sellingPrice", sellingPrice)
                        .put("subCategory", groupId)
        );
    }

    @Override
    public String getApiUrl() {
        return API_URL;
    }

    public String getSku() throws AssertionError {
        return getPropertyAsString("sku");
    }

    public String getBarCode() throws AssertionError {
        return getPropertyAsString("barcode");
    }
}
