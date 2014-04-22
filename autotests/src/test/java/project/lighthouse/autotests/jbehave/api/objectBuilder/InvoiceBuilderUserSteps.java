package project.lighthouse.autotests.jbehave.api.objectBuilder;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.steps.api.commercialManager.SupplierApiSteps;
import project.lighthouse.autotests.steps.api.objectBuilder.InvoiceBuilderSteps;

import java.io.IOException;
import java.util.Map;

public class InvoiceBuilderUserSteps {

    @Steps
    InvoiceBuilderSteps invoiceBuilderSteps;

    @Steps
    SupplierApiSteps supplierApiSteps;

    @Given("the user creates invoice api object with values $examplesTable")
    public void givenTheUserCreatesInvoiceApiObjectWithValues(ExamplesTable examplesTable) throws JSONException, IOException {
        String acceptanceDate = "",
                accepter = "",
                legalEntity = "",
                supplierInvoiceNumber = "",
                acceptanceDateTime = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("value");
            switch (elementName) {
                case "acceptanceDateTime":
                    acceptanceDateTime = elementValue;
                    break;
                case "accepter":
                    accepter = elementValue;
                    break;
                case "legalEntity":
                    legalEntity = elementValue;
                    break;
                case "supplierInvoiceNumber":
                    supplierInvoiceNumber = elementValue;
                    break;
                case "acceptanceDate":
                    if (!acceptanceDateTime.isEmpty()) {
                        String[] timeArrayValues = acceptanceDateTime.split(":");
                        acceptanceDate = new DateTimeHelper(elementValue).convertDateTime(
                                Integer.parseInt(timeArrayValues[0]),
                                Integer.parseInt(timeArrayValues[1]),
                                Integer.parseInt(timeArrayValues[2]));


                    } else {
                        acceptanceDate = DateTimeHelper.getDate(elementValue);
                    }
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }

        invoiceBuilderSteps.build(supplierApiSteps.createSupplier().getId(), acceptanceDate, accepter, legalEntity, supplierInvoiceNumber);
    }

    @Given("the user adds the product with data to invoice api object $examplesTable")
    public void givenTheUserAddsTheProductWithDataToInvoiceApiObject(ExamplesTable examplesTable) throws JSONException {
        String productName = "",
                quantity = "",
                price = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("value");
            switch (elementName) {
                case "productName":
                    productName = elementValue;
                    break;
                case "quantity":
                    quantity = elementValue;
                    break;
                case "price":
                    price = elementValue;
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }

        invoiceBuilderSteps.addProduct(
                StaticData.products.get(productName).getId(),
                quantity,
                price);
    }
}
