package ru.dreamkas.steps.api.builder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.stockmovement.supplierReturn.SupplierReturn;
import ru.dreamkas.storage.Storage;

import java.io.IOException;

public class SupplierReturnBuilderSteps extends ScenarioSteps {

    SupplierReturn supplier;

    @Step
    public void build(String date,
                      String storeId,
                      Boolean paid,
                      String supplierId) {
        try {
            supplier = new SupplierReturn(storeId, date, paid, supplierId);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void addProduct(String productId, String quantity, String price) {
        try {
            supplier.putProduct(productId, quantity, price);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void send(String email) {
        String password = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email).getPassword();
        ApiFactory factory = new ApiFactory(email, password);
        try {
            factory.createObject(supplier);
            Storage.getStockMovementVariableStorage().addSupplierReturn(supplier);
        } catch (IOException | JSONException e) {
            throw new AssertionError(e);
        }
    }
}
