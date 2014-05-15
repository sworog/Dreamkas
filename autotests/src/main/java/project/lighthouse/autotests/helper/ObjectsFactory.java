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
                Product.TYPE_UNIT,
                "0",
                "100.0",
                "barcode",
                "country",
                "vendor",
                "subCategory",
                "100",
                "0",
                null);
    }
}
