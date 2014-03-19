package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.helper.UUIDGenerator;
import project.lighthouse.autotests.objects.api.OrderProduct;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.objects.api.Supplier;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.SupplierApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.OrderApiSteps;
import project.lighthouse.autotests.storage.Storage;

import java.io.IOException;

public class OrderApiUserSteps {

    @Steps
    OrderApiSteps orderApiSteps;

    @Steps
    SupplierApiSteps supplierApiSteps;

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Given("there is the order in the store by '$userName'")
    public void createOrder(String userName) throws IOException, JSONException {
        String uuid = new UUIDGenerator().generate();
        Supplier supplier = supplierApiSteps.createSupplier(uuid);
        SubCategory subCategory = catalogApiSteps.createDefaultSubCategoryThroughPost();
        Product product = productApiSteps.createProductThroughPost(uuid, uuid, uuid, "unit", "100.00", subCategory.getName());

        Storage.getOrderVariableStorage().product = product;
        Storage.getOrderVariableStorage().supplier = supplier;

        OrderProduct orderProduct = new OrderProduct(product.getId(), "1");
        orderApiSteps.createOrder(
                supplier,
                new OrderProduct[]{orderProduct},
                userName,
                "lighthouse");
        Storage.getOrderVariableStorage().incrementNumber();
    }

    @Given("the user opens last created order page")
    public void givenTheUserOpensLastCreatedOrderPage() throws JSONException {
        orderApiSteps.openOrderPage();
    }
}
