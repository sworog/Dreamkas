package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.steps.api.invoice.InvoiceApiSteps;

import java.io.IOException;

public class GivenInvoiceApiUserSteps {

    @Steps
    InvoiceApiSteps invoiceApiSteps;

    @Given("the user with email '$email 'creates invoice with builders steps")
    public void givenTheUserWithEmailCreatesInvoiceWithBuilderSteps(String email) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceFromInvoiceBuilderStepsByUserWithEmail(email);
    }
}
