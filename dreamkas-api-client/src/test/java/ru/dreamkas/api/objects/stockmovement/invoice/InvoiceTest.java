package ru.dreamkas.api.objects.stockmovement.invoice;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class InvoiceTest {

    @Test
    public void testInvoiceJson() throws JSONException {
        assertThat(
                new Invoice("24.12.2014", false, "storeId", "supplierId").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "24.12.2014")
                        .put("paid", false)
                        .put("supplier", "supplierId").toString()));
    }

    @Test
    public void testInvoiceApiUrl() throws JSONException {
        assertThat(new Invoice("24.12.2014", false, "supplierId", "storeId").getApiUrl(), is("/invoices"));
    }

    @Test
    public void testInvoiceProductAddition() throws JSONException {
        assertThat(
                new Invoice("24.12.2014", false, "storeId", "supplierId").putProduct("productId", "1", "price").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "24.12.2014")
                        .put("paid", false)
                        .put("supplier", "supplierId")
                        .put("products", new JSONArray().put(new JSONObject()
                                .put("product", "productId")
                                .put("priceEntered", "price")
                                .put("quantity", "1")))
                        .toString()));
    }
}
