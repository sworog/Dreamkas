package project.lighthouse.autotests.steps.api.invoice;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.ApiFactory;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class InvoiceApiSteps {

    @Step
    public Invoice createInvoiceFromInvoiceBuilderStepsByUserWithEmail(String email) throws IOException, JSONException {
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        ApiFactory apiFactory = new ApiFactory(email, userContainer.getPassword());
        Invoice invoiceFromBuilderSteps = Storage.getInvoiceVariableStorage().getInvoiceForInvoiceBuilderSteps();
        Invoice invoice = ((Invoice) apiFactory.createObject(invoiceFromBuilderSteps));
        Storage.getInvoiceVariableStorage().getInvoiceList().add(invoice);
        Storage.getInvoiceVariableStorage().setInvoiceForInvoiceBuilderSteps(null);
        return invoice;
    }
}
