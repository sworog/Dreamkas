package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.helper.UUIDGenerator;
import project.lighthouse.autotests.objects.api.Group;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;

import java.io.IOException;
import java.util.Map;

public class EndProductApiSteps {

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Given("there is the product in subCategory with name '$subCategoryName' with data $exampleTable")
    public void givenThereIsTheProductWithData(String subCategoryName, ExamplesTable examplesTable) throws IOException, JSONException {
        String name = "",
                type = "",
                vat = "",
                purchasePrice = "",
                barcode = "",
                vendorCountry = "",
                vendor = "",
                retailMarkupMax = "",
                retailMarkupMin = "",
                rounding = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("elementValue");
            switch (elementName) {
                case "name":
                    name = elementValue;
                    break;
                case "type":
                    type = elementValue;
                    break;
                case "vat":
                    vat = elementValue;
                    break;
                case "purchasePrice":
                    purchasePrice = elementValue;
                    break;
                case "barcode":
                    barcode = elementValue;
                    break;
                case "vendorCountry":
                    vendorCountry = elementValue;
                    break;
                case "vendor":
                    vendor = elementValue;
                    break;
                case "retailMarkupMax":
                    retailMarkupMax = elementValue;
                    break;
                case "retailMarkupMin":
                    retailMarkupMin = elementValue;
                    break;
                case "rounding":
                    rounding = elementValue;
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }
        productApiSteps.createProduct(name, type, vat, purchasePrice, barcode, vendorCountry, vendor, subCategoryName, retailMarkupMax, retailMarkupMin, rounding);
    }

    public Product сreateProductThroughPost(String name, String barcode, String type, String purchasePrice) throws JSONException, IOException {
        if (!StaticData.hasSubCategory(SubCategory.DEFAULT_NAME)) {
            catalogApiSteps.createDefaultSubCategoryThroughPost();
        }
        return productApiSteps.createProductThroughPost(name, barcode, type, purchasePrice, SubCategory.DEFAULT_NAME, null);
    }

    @Given("there is the product with '$name' name, '$barcode' barcode")
    public void givenTheUserCreatesProductWithParams(String name, String barcode) throws JSONException, IOException {
        сreateProductThroughPost(name, barcode, Product.TYPE_UNIT, "123");
    }

    @Given("there is created product with name '$name'")
    public void givenThereIsCreatedProductWithNameValue(String name) throws JSONException, IOException {
        givenTheUserCreatesProductWithParams(name, name, Product.TYPE_UNIT);
    }

    @Given("there is the product with '$name' name, '$barcode' barcode, '$type' type")
    @Alias("there is the product with <name> name, '$barcode' barcode, '$type' type")
    public void givenTheUserCreatesProductWithParams(String name, String barcode, String type) throws JSONException, IOException {
        сreateProductThroughPost(name, barcode, type, "123");
    }

    @Given("there is the product with <productName> name, '$type' type")
    @Alias("there is the product with '$productName' name, '$type' type")
    public void givenTheUserCreatesProductWithType(String productName, String type) throws JSONException, IOException {
        сreateProductThroughPost(productName, "000", type, "123");
    }

    @Given("there is the product with '$name' name, '$barcode' barcode, '$type' type, '$purchasePrice' purchasePrice")
    public void givenTheUserCreatesProductWithParamsPrice(String name, String barcode, String type, String purchasePrice) throws JSONException, IOException {
        сreateProductThroughPost(name, barcode, type, purchasePrice);
    }

    @Given("there is the product with '$name' name, '$barcode' barcode, '$type' type, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String barcode, String type, String purchasePrice,
                                         String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(name, barcode, type, purchasePrice, subCategoryName, null);
    }

    @Given("there is the product with '$name' name, '$barcode' barcode, '$type' type, '$purchasePrice' purchasePrice, markup min '$min' max '$max' of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String barcode, String type, String purchasePrice,
                                         String groupName, String categoryName, String subCategoryName, String min, String max) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(name, barcode, type, purchasePrice, subCategoryName, max, min, null);
    }

    @Given("there is the product with '$name' name, '$barcode' barcode, '$type' type, '$purchasePrice' purchasePrice, '$nameOfScales' nameOfScales, '$descriptionOnScales' descriptionOnScales, '$ingredients' ingredients, '$nutritionFacts' nutritionFacts, '$shelfLife' shelfLife, markup min '$min' max '$max' of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String barcode, String type, String purchasePrice,
                                         String groupName, String categoryName, String subCategoryName, String min, String max,
                                         String nameOfScales, String descriptionOnScales, String ingredients,
                                         String nutritionFacts, String shelfLife) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(name, barcode, type, purchasePrice, subCategoryName, max, min, null,
                nameOfScales, descriptionOnScales, ingredients, nutritionFacts, shelfLife);
    }

    @Given("there is the alcohol product with '$name' name, '$barcode' barcode, '$purchasePrice' purchasePrice, '$alcoholByVolume' alcoholByVolume, '$volume' volume, markup min '$min' max '$max' of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName'")
    public void createProductThroughPost(String name, String barcode, String purchasePrice,
                                         String alcoholByVolume, String volume,
                                         String groupName, String categoryName, String subCategoryName, String min, String max) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(name, barcode, "alcohol", purchasePrice, subCategoryName, max, min, null,
                alcoholByVolume, volume);
    }

    @Given("there is the product with '$productName' name, '$barcode' barcode, '$type' type, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding")
    @Alias("there is the product with productName, '$barcode' barcode, '$type' type, '$purchasePrice' purchasePrice of group named '$groupName', category named '$categoryName', subcategory named '$subCategoryName' with '$rounding' rounding")
    public void createProductThroughPost(String productName, String barcode, String type, String purchasePrice,
                                         String rounding, String groupName, String categoryName, String subCategoryName) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(groupName, categoryName, subCategoryName);
        productApiSteps.createProductThroughPost(productName, barcode, type, purchasePrice, subCategoryName, rounding);
    }

    @Given("there is the product with productName and rounding in the subcategory named '$subCategoryName'")
    public void createProductThroughPost(String rounding, String productName, String subCategoryName) throws IOException, JSONException {
        catalogApiSteps.createSubCategoryThroughPost(Group.DEFAULT_NAME, SubCategory.DEFAULT_NAME, subCategoryName);
        productApiSteps.createProductThroughPost(productName, productName, Product.TYPE_WEIGHT, "1", subCategoryName, rounding);
    }

    @Given("the user navigates to the product with name '$productName'")
    @Alias("the user navigates to the product with productName")
    public void givenTheUserNavigatesToTheProduct(String productName) throws JSONException {
        productApiSteps.navigateToTheProductPage(productName);
    }

    @Given("the user navigates to the product with name <productName>")
    public void givenTheUserNavigatesToTheProductAlias(String productName) throws JSONException {
        productApiSteps.navigateToTheProductPage(productName);
    }

    @Given("the user navigates to the product with name")
    public void givenTheUserNavigatesToTheProductWithName(String name) throws JSONException, IOException {
        givenTheUserCreatesProductWithParamsPrice(name, name, Product.TYPE_WEIGHT, "0,01");
        givenTheUserNavigatesToTheProduct(name);
    }

    @Given("the user navigates to the product with random name")
    public void givenTheUserNavigatesToTheProductWithRandomName() throws IOException, JSONException {
        String uuid = new UUIDGenerator().generate();
        givenTheUserCreatesProductWithParamsPrice(uuid, uuid, Product.TYPE_WEIGHT, "0,01");
        givenTheUserNavigatesToTheProduct(uuid);
    }
}
