package ru.dreamkas.api.objects.stockmovement;

import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;
import ru.dreamkas.api.objects.stockmovement.invoice.Invoice;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class StockMovementTest {

    @Test
    public void testGetStockMovementNumber() throws JSONException {
        Invoice invoice = new Invoice("24.12.2014", false, "supplierId", "storeId");
        invoice.setJsonObject(new JSONObject().put("number", "12345"));
        assertThat(invoice.getNumber(), is("12345"));
    }
}
