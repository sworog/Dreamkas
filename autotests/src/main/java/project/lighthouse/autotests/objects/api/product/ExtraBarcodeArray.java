package project.lighthouse.autotests.objects.api.product;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.abstraction.AbstractObject;

import java.util.List;

/**
 * Array to store product extra barcodes
 */
public class ExtraBarcodeArray extends AbstractObject {

    private Product product;

    @Override
    public String getApiUrl() {
        return String.format("/products/%s/barcodes", product.getId());
    }

    public ExtraBarcodeArray(List<ExtraBarcode> extraBarcodes) throws JSONException {
        JSONArray jsonBarcodeArray = new JSONArray();
        for (ExtraBarcode extraBarcode : extraBarcodes) {
            jsonBarcodeArray.put(extraBarcode.getJsonObject());
        }
        setJsonObject(new JSONObject().put("barcodes", jsonBarcodeArray));
    }

    public void setProduct(Product product) {
        this.product = product;
    }
}
