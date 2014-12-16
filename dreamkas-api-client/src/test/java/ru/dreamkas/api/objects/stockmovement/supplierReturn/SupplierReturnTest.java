package ru.dreamkas.api.objects.stockmovement.supplierReturn;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class SupplierReturnTest {

    @Test
    public void testSupplierReturnInJson() throws JSONException {
        assertThat(
                new SupplierReturn("storeId", "24.12.2014", false, "supplierId").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "24.12.2014")
                        .put("paid", false)
                        .put("supplier", "supplierId").toString()));
    }

    @Test
    public void testSupplierReturnApiUrl() throws JSONException {
        assertThat(new SupplierReturn("supplierId", "24.12.2014", false, "storeId").getApiUrl(), is("/supplierReturns"));
    }

    @Test
    public void testSupplierReturnProductAddition() throws JSONException {
        assertThat(
                new SupplierReturn("storeId", "24.12.2014", false, "supplierId").putProduct("productId", "1", "price").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "24.12.2014")
                        .put("paid", false)
                        .put("supplier", "supplierId")
                        .put("products", new JSONArray().put(new JSONObject()
                                .put("product", "productId")
                                .put("price", "price")
                                .put("quantity", "1")))
                        .toString()));
    }
}
