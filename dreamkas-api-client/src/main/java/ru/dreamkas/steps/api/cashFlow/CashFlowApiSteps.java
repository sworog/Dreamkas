package ru.dreamkas.steps.api.cashFlow;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.cashFlow.CashFlow;
import ru.dreamkas.apiStorage.ApiStorage;
import ru.dreamkas.apiStorage.containers.user.UserContainer;
import ru.dreamkas.apiStorage.variable.CustomVariableStorage;
import ru.dreamkas.apihelper.DateTimeHelper;

import java.io.IOException;

public class CashFlowApiSteps extends ScenarioSteps {

    @Step
    public CashFlow createCashFlow(String direction, String date, String amount, String comment, String email) throws JSONException, IOException {
        String convertedDate = DateTimeHelper.getDate(date);
        CashFlow cashFlow = new CashFlow(direction, convertedDate, amount, comment);
        UserContainer userContainer = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        CustomVariableStorage customVariableStorage = ApiStorage.getCustomVariableStorage();
        String key = direction+convertedDate+amount+comment;
        if (!customVariableStorage.getCashFlowHashMap().containsKey(key)) {
            new ApiFactory(userContainer.getEmail(), userContainer.getPassword()).createObject(cashFlow);
            customVariableStorage.getCashFlowHashMap().put(key, cashFlow);
            return cashFlow;
        } else {
            return customVariableStorage.getCashFlowHashMap().get(key);
        }
    }
}
