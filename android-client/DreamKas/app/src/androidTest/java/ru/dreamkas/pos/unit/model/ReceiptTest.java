package ru.dreamkas.pos.unit.model;

import android.test.AndroidTestCase;
import android.test.InstrumentationTestCase;

import java.math.BigDecimal;

import ru.dreamkas.pos.Constants;
import ru.dreamkas.pos.model.Receipt;
import ru.dreamkas.pos.model.api.Product;

import static org.hamcrest.MatcherAssert.assertThat;
import static org.hamcrest.Matchers.is;

public class ReceiptTest extends AndroidTestCase {
    private Receipt mReceipt;

    @Override
    protected void setUp() throws Exception {
        super.setUp();

        mReceipt = new Receipt();
    }

    public void testReceiptFill() {

        Product product = new Product();
        Product product2 = new Product();

        mReceipt.add(product);
        mReceipt.add(product2);

        assertThat("Wrong receipt size after products add", mReceipt.size(), is(2));
    }

    public void testReceiptClear() {

        Product product = new Product();
        Product product2 = new Product();

        mReceipt.add(product);
        mReceipt.add(product2);

        mReceipt.clear();

        assertThat("Receipt should be empty after clear", mReceipt.size(), is(0));
    }

    public void testReceiptTotalAfterClear() {

        Product product = new Product();
        Product product2 = new Product();

        mReceipt.add(product);
        mReceipt.add(product2);

        mReceipt.clear();

        assertThat("Wrong receipt total after clear", mReceipt.getTotal(), is(BigDecimal.ZERO.setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP)));
    }

    public void testReceiptTotal() {

        Product product = new Product();
        product.setSellingPrice(new BigDecimal(150));

        Product product2 = new Product();
        product2.setSellingPrice(new BigDecimal(100));

        Product product3 = new Product();
        product3.setSellingPrice(new BigDecimal(23));

        mReceipt.add(product);
        mReceipt.add(product2);
        mReceipt.add(product3);

        assertThat("Wrong receipt total", mReceipt.getTotal(), is(new BigDecimal(273).setScale(Constants.SCALE_MONEY, BigDecimal.ROUND_HALF_UP)));
    }

}
