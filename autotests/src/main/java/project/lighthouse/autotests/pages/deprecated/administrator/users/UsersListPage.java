package project.lighthouse.autotests.pages.deprecated.administrator.users;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.items.NonType;

@Deprecated
@DefaultUrl("/users")
public class UsersListPage extends CommonPageObject {

    public static final String ITEM_NAME = "user";
    private static final String ITEM_SKU_NAME = "username";

    CommonView commonView = new CommonView(getDriver(), ITEM_NAME, ITEM_SKU_NAME);

    public UsersListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new NonType(this, "name"));
        put("position", new NonType(this, "position"));
        put("username", new NonType(this, "username"));
        put("password", new NonType(this, "password"));
        put("role", new NonType(this, "role"));
    }

    public void createNewUserButtonClick() {
        new ButtonFacade(this, "Добавить пользователя").click();
    }

    public void listItemClick(String skuValue) {
        commonView.itemClick(skuValue);
    }

    public void listItemCheck(String skuValue) {
        commonView.itemCheck(skuValue);
    }

    public void listItemCheckIsNotPresent(String value) {
        commonView.itemCheckIsNotPresent(value);
    }

    public void checkListItemHasExpectedValueByFindByLocator(String value, String elementName, String expectedValue) {
        By findBy = getItems().get(elementName).getFindBy();
        commonView.checkListItemHasExpectedValueByFindByLocator(value, elementName, findBy, expectedValue);
    }
}
