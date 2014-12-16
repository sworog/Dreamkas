package ru.dreamkas.api.objects.stockmovement.writeOff;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;
import ru.dreamkas.api.objects.stockmovement.writeoff.WriteOff;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class WriteOffTest {

    @Test
    public void testWriteOffJson() throws JSONException {
        assertThat(
                new WriteOff("storeId", "24.12.2014").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "24.12.2014").toString()));
    }

    @Test
    public void testWriteOffApiUrl() throws JSONException {
        assertThat(new WriteOff("", "").getApiUrl(), is("/writeOffs"));
    }

    @Test
    public void testWriteOffProductAddition() throws JSONException {
        assertThat(
                new WriteOff("storeId", "date").putProduct("productId", "1", "price", "cause").getJsonObject().toString(),
                is(new JSONObject()
                        .put("store", "storeId")
                        .put("date", "date")
                        .put("products", new JSONArray().put(new JSONObject()
                                .put("product", "productId")
                                .put("price", "price")
                                .put("quantity", "1")
                                .put("cause", "cause")))
                        .toString()));
    }
}
