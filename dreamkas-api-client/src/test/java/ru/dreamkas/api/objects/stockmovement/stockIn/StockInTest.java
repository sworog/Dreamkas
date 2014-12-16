package ru.dreamkas.api.objects.stockmovement.stockIn;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;
import ru.dreamkas.api.objects.stockmovement.stockin.StockIn;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StockInTest {

    @Test
    public void testStockInJson() throws JSONException {
        assertThat(
                new StockIn("storeId", "24.12.2014").getJsonObject().toString(),
                is(new JSONObject()
                    .put("store", "storeId")
                    .put("date", "24.12.2014").toString()));
    }

    @Test
    public void testStockInApiUrl() throws JSONException {
        assertThat(new StockIn("", "").getApiUrl(), is("/stockIns"));
    }

    @Test
    public void testStockInProductAddition() throws JSONException {
        assertThat(
                new StockIn("storeId", "date").putProduct("productId", "1", "price").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "date")
                        .put("products", new JSONArray().put(new JSONObject()
                                                                .put("product", "productId")
                                                                .put("price", "price")
                                                                .put("quantity", "1")))
                        .toString()));
    }
}
