package ru.dreamkas.steps.api.builder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import ru.dreamkas.api.factories.ApiFactory;
import ru.dreamkas.api.objects.stockmovement.invoice.Invoice;
import ru.dreamkas.apiStorage.ApiStorage;

import java.io.IOException;

public class InvoiceBuilderSteps extends ScenarioSteps {

    Invoice invoice;

    @Step
    public void build(String date,
                      Boolean paid,
                      String storeId,
                      String supplierId) throws JSONException {
        try {
            invoice = new Invoice(date, paid, storeId, supplierId);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void addProduct(String productId, String quantity, String price) {
        try {
            invoice.putProduct(productId, quantity, price);
        } catch (JSONException e) {
            throw new AssertionError(e);
        }
    }

    @Step
    public void send(String email) {
        String password = ApiStorage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email).getPassword();
        ApiFactory factory = new ApiFactory(email, password);
        try {
            factory.createObject(invoice);
            ApiStorage.getStockMovementVariableStorage().addStockMovement(invoice);
        } catch (IOException | JSONException e) {
            throw new AssertionError(e);
        }
    }
}
