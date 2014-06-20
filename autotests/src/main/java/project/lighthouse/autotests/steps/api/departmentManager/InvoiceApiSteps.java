package project.lighthouse.autotests.steps.api.departmentManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.api.factories.InvoicesFactory;
import project.lighthouse.autotests.helper.UrlHelper;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.objects.api.invoice.InvoiceProduct;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.containers.user.UserContainer;

import java.io.IOException;
import java.util.ArrayList;
import java.util.List;

public class InvoiceApiSteps extends DepartmentManagerApi {

    private List<Invoice> invoiceList = new ArrayList<>();

    @Step
    public Invoice createInvoice(String supplierId,
                                 String acceptanceDate,
                                 String accepter,
                                 String legalEntity,
                                 String supplierInvoiceNumber,
                                 String userName,
                                 InvoiceProduct[] invoiceProducts) throws IOException, JSONException {
        User user = StaticData.users.get(userName);
        Invoice invoice = new InvoicesFactory(userName, "lighthouse")
                .create(supplierId, acceptanceDate, accepter, legalEntity, supplierInvoiceNumber, invoiceProducts, user.getStore());
        this.invoiceList.add(invoice);
        return invoice;
    }

    @Step
    public void openLastStoredInvoicePage() throws JSONException {
        navigateToInvoicePage(getLastStoredInvoiceListItem());
    }

    @Step
    public void openOneInvoiceAgoStoredInvoicePage() throws JSONException {
        navigateToInvoicePage(getOneInvoiceAgoListItem());
    }

    @Step
    public void openTwoInvoiceAgoStoredInvoicePage() throws JSONException {
        navigateToInvoicePage(getTwoInvoiceAgoListItem());
    }

    private void navigateToInvoicePage(Invoice invoice) {
        String invoicePageUrl = String.format("%s/stores/%s/invoices/%s",
                UrlHelper.getWebFrontUrl(),
                invoice.getStore().getId(),
                invoice.getId());
        getDriver().navigate().to(invoicePageUrl);
    }

    /**
     * The method depends on invoice object creating by
     * First - {@link project.lighthouse.autotests.steps.api.objectBuilder.InvoiceBuilderSteps#build(String, String, String, String, String)}}
     * Second {@link project.lighthouse.autotests.steps.api.objectBuilder.InvoiceBuilderSteps#addProduct(String, String, String)}
     *
     * @param userName
     * @return
     * @throws IOException
     * @throws JSONException
     */
    @Step
    @Deprecated
    public Invoice createInvoiceFromInvoiceBuilderSteps(String userName) throws IOException, JSONException {
        User user = StaticData.users.get(userName);
        Invoice invoice = new InvoicesFactory(userName, "lighthouse")
                .create(
                        Storage.getInvoiceVariableStorage().getInvoiceForInvoiceBuilderSteps(),
                        user.getStore()
                );
        invoiceList.add(invoice);
        Storage.getInvoiceVariableStorage().setInvoiceForInvoiceBuilderSteps(null);
        return invoice;
    }

    @Step
    public Invoice createInvoiceFromInvoiceBuilderStepsByUserWithEmail(String email) throws IOException, JSONException {
        UserContainer userContainer = Storage.getUserVariableStorage().getUserContainers().getContainer(email);
        Invoice invoice = new InvoicesFactory(email, userContainer.getPassword())
                .create(
                        Storage.getInvoiceVariableStorage().getInvoiceForInvoiceBuilderSteps(),
                        userContainer.getStore()
                );
        invoiceList.add(invoice);
        Storage.getInvoiceVariableStorage().setInvoiceForInvoiceBuilderSteps(null);
        return invoice;
    }

    private Invoice getLastStoredInvoiceListItem() {
        return invoiceList.get(invoiceList.size() - 1);
    }

    private Invoice getOneInvoiceAgoListItem() {
        return invoiceList.get(invoiceList.size() - 2);
    }

    private Invoice getTwoInvoiceAgoListItem() {
        return invoiceList.get(invoiceList.size() - 3);
    }
}
