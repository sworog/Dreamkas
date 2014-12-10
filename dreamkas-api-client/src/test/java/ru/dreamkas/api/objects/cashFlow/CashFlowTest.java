package ru.dreamkas.api.objects.cashFlow;

import org.json.JSONException;
import org.json.JSONObject;
import org.junit.Test;

import static org.hamcrest.Matchers.is;
import static org.junit.Assert.assertThat;

public class CashFlowTest {

    @Test
    public void cashFlowApiUrlShouldBeCorrect() throws JSONException {
        assertThat(
                new CashFlow("direction", "date", "amount", "comment").getApiUrl(),
                is("/cashFlows"));

    }

    /*
    Had to cast json object to string to compare them
     */
    @Test
    public void cashFlowContainCorrectJson() throws JSONException {
        CashFlow cashFlow = new CashFlow("in", "12.10.2014", "56.89", "had to");
        assertThat(cashFlow.getJsonObject().toString(), is(new JSONObject()
                        .put("direction", "in")
                        .put("date", "12.10.2014")
                        .put("amount", "56.89")
                        .put("comment", "had to").toString()));
    }
}
