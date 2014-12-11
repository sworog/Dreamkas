package ru.dreamkas.api.objects.cashFlow;

import org.json.JSONException;
import ru.dreamkas.api.objects.abstraction.AbstractObject;

public class CashFlow extends AbstractObject {

    public CashFlow(String direction, String date, String amount, String comment) throws JSONException {
        super();
        getJsonObject()
                .put("direction", direction)
                .put("date", date)
                .put("amount", amount)
                .put("comment", comment);
    }

    @Override
    public String getApiUrl() {
        return "/cashFlows";
    }
}
