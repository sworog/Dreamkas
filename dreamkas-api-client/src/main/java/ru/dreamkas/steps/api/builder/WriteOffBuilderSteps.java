package ru.dreamkas.steps.api.builder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.stockmovement.writeoff.WriteOff;
import ru.dreamkas.apiStorage.ApiStorage;

import java.io.IOException;

public class WriteOffBuilderSteps extends ScenarioSteps {

    WriteOff writeOff;

    @Step
    public void build(String storeId, String date) {
        try {
            writeOff = new WriteOff(storeId, date);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void addProduct(String productId, String quantity, String price, String cause) {
        try {
            writeOff.putProduct(productId, quantity, price, cause);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void send(String email) {
        String password = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email).getPassword();
        ApiFactory factory = new ApiFactory(email, password);
        try {
            factory.createObject(writeOff);
            ApiStorage.getStockMovementVariableStorage().addStockMovement(writeOff);
        } catch (IOException | JSONException e) {
            throw new AssertionError(e);
        }
    }
}
