package ru.dreamkas.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import ru.dreamkas.steps.api.cashFlow.CashFlowApiSteps;

import java.io.IOException;

public class GivenCashFlowApiUserSteps {

    @Steps
    CashFlowApiSteps cashFlowApiSteps;

    @Given("пользователь с адресом электронной почты '$email' создает денежную операцию с направлением '$direction', датой '$date', суммой '$amount' и комментарием '$comment'")
    public void givenTheUserWithEmailCreatesCashFlowWithParams(String email, String direction, String date, String amount, String comment) throws IOException, JSONException {
        cashFlowApiSteps.createCashFlow(direction, date, amount, comment, email);
    }
}
