package project.lighthouse.autotests.helper;

import org.json.JSONException;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Supplier;

/**
 * Objects factory
 */
public class ObjectsFactory {

    public static Supplier getSupplierObject() throws JSONException {
        return new Supplier("supplier");
    }

    public static Product getProductObject() throws JSONException {
        return new Product(
                "name",
                "liter",
                "0",
                "100.0",
                "barcode",
                "country",
                "vendor",
                "info",
                "subCategory",
                "100",
                "0",
                null);
    }
}
