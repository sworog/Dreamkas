package project.lighthouse.autotests.steps.api.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.api.objects.Supplier;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.variable.CustomVariableStorage;

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
        String password = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(userEmail).getPassword();
        CustomVariableStorage customVariableStorage = Storage.getCustomVariableStorage();

        if (!customVariableStorage.getSuppliers().containsKey(supplier.getName())) {
            new ApiFactory(userEmail, password).createObject(supplier);
            customVariableStorage.getSuppliers().put(supplier.getName(), supplier);
            return supplier;
        } else {
            return customVariableStorage.getSuppliers().get(supplier.getName());
        }
    }
}
