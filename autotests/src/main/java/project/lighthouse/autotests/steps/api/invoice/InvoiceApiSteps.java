package project.lighthouse.autotests.steps.api.invoice;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.api.factories.InvoicesFactory;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;

public class InvoiceApiSteps {

    @Step
    public Invoice createInvoiceFromInvoiceBuilderStepsByUserWithEmail(String email) throws IOException, JSONException {
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainerWithEmail(email);
        Invoice invoice = new InvoicesFactory(email, userContainer.getPassword())
                .create(
                        Storage.getInvoiceVariableStorage().getInvoiceForInvoiceBuilderSteps()
                );

        Storage.getInvoiceVariableStorage().getInvoiceList().add(invoice);
        Storage.getInvoiceVariableStorage().setInvoiceForInvoiceBuilderSteps(null);
        return invoice;
    }
}
