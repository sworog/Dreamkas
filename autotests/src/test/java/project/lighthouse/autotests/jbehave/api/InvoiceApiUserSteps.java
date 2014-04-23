package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.helper.UUIDGenerator;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.objects.api.Supplier;
import project.lighthouse.autotests.objects.api.invoice.InvoiceProduct;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.SupplierApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;
import project.lighthouse.autotests.storage.Storage;

import java.io.IOException;

public class InvoiceApiUserSteps {

    @Steps
    SupplierApiSteps supplierApiSteps;

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    InvoiceApiSteps invoiceApiSteps;

    @Given("there is the invoice in the store by '$userName'")
    public void createOrder(String userName) throws IOException, JSONException {
        String quantity = "1";
        String enteredPrice = "100.00";
        String acceptanceDate = new DateTimeHelper(0).convertDateTime();
        String accepter = "accepter";
        String legalEntity = "legalEntity";
        String supplierInvoiceNumber = "supplierInvoiceNumber";
        String uuid = new UUIDGenerator().generate();
        Supplier supplier = supplierApiSteps.createSupplier(uuid);
        SubCategory subCategory = catalogApiSteps.createDefaultSubCategoryThroughPost();
        Product product = productApiSteps.createProductThroughPost(uuid, uuid, "unit", "100.00", subCategory.getName());

        InvoiceProduct invoiceProduct = new InvoiceProduct(product.getId(), quantity, enteredPrice);

        invoiceApiSteps.createInvoice(
                supplier.getId(),
                acceptanceDate,
                accepter,
                legalEntity,
                supplierInvoiceNumber,
                userName,
                new InvoiceProduct[]{invoiceProduct});

        Storage.getInvoiceVariableStorage()
                .setProduct(product)
                .setSupplier(supplier)
                .setQuantity(quantity)
                .setPrice(enteredPrice)
                .setAcceptanceDate(acceptanceDate)
                .setAccepter(accepter)
                .setLegalEntity(legalEntity)
                .setSupplierInvoiceNumber(supplierInvoiceNumber)
                .incrementNumber();
    }

    @Given("the user opens last created invoice page")
    public void givenTheUserOpensLastCreatedOrderPage() throws JSONException {
        invoiceApiSteps.openLastStoredInvoicePage();
    }

    @Given("the user opens one invoice ago created invoice page")
    public void givenTheUserOpensPreviousCreatedInvoicePage() throws JSONException {
        invoiceApiSteps.openOneInvoiceAgoStoredInvoicePage();
    }

    @Given("the user opens two invoice ago created invoice page")
    public void givenTheUserOpensTwoInvoiceAgoInvoice() throws JSONException {
        invoiceApiSteps.openTwoInvoiceAgoStoredInvoicePage();
    }

    @Given("there is the invoice created with invoice builder steps by userName '$userName'")
    public void givenThereIsTheInvoiceCreatedWithInvoiceBuilderStepsByUserName(String userName) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceFromInvoiceBuilderSteps(userName);
    }
}
