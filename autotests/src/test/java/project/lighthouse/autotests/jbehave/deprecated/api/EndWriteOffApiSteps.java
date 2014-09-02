package project.lighthouse.autotests.jbehave.deprecated.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.elements.items.DateTime;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.steps.deprecated.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.deprecated.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.deprecated.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.deprecated.api.departmentManager.WriteOffApiSteps;
import project.lighthouse.autotests.steps.deprecated.departmentManager.WriteOffSteps;

import java.io.IOException;
import java.util.Map;

public class EndWriteOffApiSteps {

    @Steps
    WriteOffApiSteps writeOffApiSteps;

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
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, DateTimeHelper.getTodayDate(DateTime.DATE_PATTERN), store.getNumber(), user.getUserName());
    }

    @Given("there is the write off with number '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    @Alias("there is the write off with sku '$writeOffNumber' in the store with number '$storeNumber' ruled by user with name '$userName'")
    public void givenThereIsTheWriteOffWithNumberInTheStoreRuledByUser(String writeOffNumber, String storeNumber, String userName) throws IOException, JSONException {
        writeOffApiSteps.createWriteOffThroughPost(writeOffNumber, DateTimeHelper.getTodayDate(DateTime.DATE_PATTERN), storeNumber, userName);
    }

    @Given("the user adds the product to the write off with number '$writeOffNumber' with name '$productName', quantity '$quantity', price '$price', cause '$cause' in the store ruled by '$userName'")
    public void addProductToWriteOff(String writeOffNumber, String productName, String quantity, String price, String cause, String userName) throws IOException, JSONException {
        writeOffApiSteps.addProductToWriteOff(writeOffNumber, productName, quantity, price, cause, userName);
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
}
