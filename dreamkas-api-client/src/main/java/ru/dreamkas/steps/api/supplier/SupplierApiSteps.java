package ru.dreamkas.steps.api.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.Supplier;
import ru.dreamkas.storage.Storage;
import ru.dreamkas.storage.variable.CustomVariableStorage;

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
