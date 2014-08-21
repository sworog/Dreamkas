package project.lighthouse.autotests.steps.api.builder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.api.objects.stockmovement.stockin.StockIn;
import project.lighthouse.autotests.api.objects.stockmovement.writeoff.WriteOff;
import project.lighthouse.autotests.storage.Storage;

import java.io.IOException;

public class StockInBuilderSteps extends ScenarioSteps {

    StockIn stockIn;

    @Step
    public void build(String storeId, String date) {
        try {
            stockIn = new StockIn(storeId, date);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void addProduct(String productId, String quantity, String price) {
        try {
            stockIn.putProduct(productId, quantity, price);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void send(String email) {
        String password = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email).getPassword();
        ApiFactory factory = new ApiFactory(email, password);
        try {
            factory.createObject(stockIn);
            Storage.getStockMovementVariableStorage().addStockMovement(stockIn);
        } catch (IOException | JSONException e) {
            throw new AssertionError(e);
        }
    }
}
