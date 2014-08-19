package project.lighthouse.autotests.steps.api.builder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.objects.api.writeoff.WriteOff;
import project.lighthouse.autotests.objects.api.writeoff.WriteOffProduct;
import project.lighthouse.autotests.storage.Storage;
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
            WriteOffProduct writeOffProduct = new WriteOffProduct(productId, quantity, price, cause);
            writeOff.putProduct(productId, quantity, price, cause);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void send(String email) {
        String password = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email).getPassword();
        ApiFactory factory = new ApiFactory(email, password);
        try {
            factory.createObject(writeOff);
        } catch (IOException | JSONException e) {
            throw new AssertionError(e);
        }
    }
}
