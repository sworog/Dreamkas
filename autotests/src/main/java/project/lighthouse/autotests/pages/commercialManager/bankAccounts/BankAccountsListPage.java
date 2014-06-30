package project.lighthouse.autotests.pages.commercialManager.bankAccounts;

import junit.framework.Assert;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;

public class BankAccountsListPage extends CommonPageObject {
    public BankAccountsListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void createNewBankAccountLinkClick() {
        clickByContainsTextLink("Добавить расчетный счет");
    }

    public void bankAccountListItemByBankAndAccountClick(String bankName, String account) {
        // TODO: как-то находим и кликаем
    }

    public void bankAccountListItemByBankClick(String bankName) {
        click(By.xpath("//tr/td[contains(text(), '" + bankName + "')]"));
    }

    public void assertExistsListItemWithAccountAndBank(String account, String bankName) {
        WebElement listItem = findElement(By.xpath("//td[contains(text(), '" + account + "')]/.."));
        WebElement accountTd = listItem.findElement(By.xpath("//td[contains(text(), '" + account + "')]"));
        WebElement bankNameTd = listItem.findElement(By.xpath("//td[contains(text(), '" + bankName + "')]"));

        if (null == accountTd || null == bankNameTd) {
            String message = String.format("Bank account with account '%s' and bank name '%s' not found", account, bankName);
            Assert.fail(message);
        }
    }
}
