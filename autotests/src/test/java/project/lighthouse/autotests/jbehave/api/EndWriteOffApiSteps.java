package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.WriteOffApiSteps;
import project.lighthouse.autotests.steps.departmentManager.WriteOffSteps;

import java.io.IOException;
import java.util.Map;

public class EndWriteOffApiSteps {

    @Steps
    WriteOffApiSteps writeOffApiSteps;

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Steps
    UserApiSteps userApiSteps;

    @Given("there is the write off with number '$writeOffNumber'")
    public void givenThereIsTheWriteOffWithNumber(String writeOffNumber) throws IOException, JSONException {
        Store store = storeApiSteps.createStoreThroughPost();
        User user = userApiSteps.getUser("departmentManager");
        catalogApiSteps.promoteDepartmentManager(store, user.getUserName());
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN), store.getNumber(), user.getUserName());
    }

    @Given("there is the write off with number '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    @Alias("there is the write off with sku '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    public void givenThereIsTheWriteOffWithNumberInTheStoreRuledByUser(String writeOffNumber, String storeNumber, String userName) throws IOException, JSONException {
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, DateTime.getTodayDate(DateTime.DATE_PATTERN), storeNumber, userName);
    }

    @Given("there is the write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProduct(String writeOffNumber, String productSku, String quantity, String price, String cause)
            throws IOException, JSONException {
        createProduct(productSku, productSku, productSku, "kg", "15");
        givenThereIsTheWriteOffWithNumber(writeOffNumber);
        writeOffApiSteps.addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, "departmentManager");
    }

    @Given("the user adds the product to the write off with number '$writeOffNumber' with sku '$productSku', quantity '$quantity', price '$price, cause '$cause' in the store ruled by '$userName'")
    public void addProductToWriteOff(String writeOffNumber, String productSku, String quantity, String price, String cause, String userName) throws IOException, JSONException {
        writeOffApiSteps.addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, userName);
    }

    @Given("the user navigates to new write off with '$writeOffNumber' number with product '$productSku' with quantity '$quantity', price '$price' and cause '$cause'")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber, String productSku, String productUnits, String purchasePrice, String quantity, String price, String cause)
            throws IOException, JSONException {
        createProduct(productSku, productSku, productSku, productUnits, purchasePrice);
        givenThereIsTheWriteOffWithNumber(writeOffNumber);
        writeOffApiSteps.addProductToWriteOff(writeOffNumber, productSku, quantity, price, cause, "departmentManager");
    }

    @Given("navigate to new write off with '$writeOffNumber' number")
    public void givenThereIsTheWriteOffWithProductWithNavigation(String writeOffNumber) throws IOException, JSONException {
        givenThereIsTheWriteOffWithNumber(writeOffNumber);
        writeOffApiSteps.navigateToWriteOffPage(writeOffNumber);
    }

    @Given("the user navigates to the write off with number '$writeOffNumber'")
    public void givenNavigateToTheWriteOffWithNumber(String writeNumber) throws JSONException {
        writeOffApiSteps.navigateToWriteOffPage(writeNumber);
    }

    @Given("there is the writeOff in the store with number '$number' ruled by department manager with name '$userName' with values $exampleTable")
    public void givenThereIsTheWriteOffInTheStoreWithValues(String number, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
        String writeOffNumber = "", date = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("elementValue");
            switch (elementName) {
                case "number":
                    writeOffNumber = elementValue;
                    break;
                case "date":
                    date = elementValue;
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, date, number, userName);
        WriteOffSteps.examplesTable = examplesTable;
    }

    public void createProduct(String productSku, String productName, String productBarCode, String productUnits, String purchasePrice) throws IOException, JSONException {
        if (!StaticData.products.containsKey(productSku)) {
            productApiSteps.createProductThroughPost(productName, productSku, productBarCode, productUnits, purchasePrice, SubCategory.DEFAULT_NAME);
        }
    }
}
