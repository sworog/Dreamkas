package project.lighthouse.autotests.steps.api.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.ApiConnect;
import project.lighthouse.autotests.objects.api.Supplier;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class SupplierApiSteps extends ScenarioSteps {

    @Step
    public Supplier createSupplierByUserWithEmail(String userEmail,
                                                  String name,
                                                  String address,
                                                  String phone,
                                                  String email,
                                                  String contactPerson) throws IOException, JSONException {
        Supplier supplier = new Supplier(name, address, phone, email, contactPerson);
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(userEmail);
        return new ApiConnect(userContainer.getEmail(), userContainer.getPassword()).createSupplier(supplier);
    }
}
