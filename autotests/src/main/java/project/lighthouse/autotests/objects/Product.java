package project.lighthouse.autotests.objects;

import org.json.JSONException;
import org.json.JSONObject;

public class Product {

    JSONObject jsonObject;

    public Product(JSONObject jsonObject) {
        this.jsonObject = jsonObject;
    }

    public String getId() throws JSONException {
        return jsonObject.getString("id");
    }

    public static JSONObject getJsonObject(String name, String units, String vat, String purchasePrice, String barcode,
                                           String sku, String vendorCountry, String vendor, String info,
                                           String subCategory, String retailMarkupMax,
                                           String retailMarkupMin) throws JSONException {
        return new JSONObject()
                .put("name", name)
                .put("units", units)
                .put("vat", vat)
                .put("purchasePrice", purchasePrice)
                .put("barcode", barcode)
                .put("sku", sku)
                .put("vendorCountry", vendorCountry)
                .put("vendor", vendor)
                .put("info", info)
                .put("subCategory", subCategory)
                .put("retailMarkupMax", retailMarkupMax)
                .put("retailMarkupMin", retailMarkupMin);
    }
}
