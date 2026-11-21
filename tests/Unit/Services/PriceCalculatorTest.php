<?php

namespace Tests\Unit\Services;

use App\Models\PricingConfiguration;
use App\Models\Product;
use App\Services\Catalog\CatalogData;
use App\Services\Catalog\PriceCalculator;
use App\Services\Catalog\ProductConfigurationValidator;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class PriceCalculatorTest extends TestCase
{
    public function test_it_applies_tiers_fees_and_the_minimum_total_at_quantity_boundaries(): void
    {
        $pricing = $this->pricing([
            'option_surcharges' => [7 => 500],
            'quantity_tiers' => [
                ['min' => 10, 'max' => 19, 'discount_per_unit' => 200],
                ['min' => 20, 'max' => 30, 'discount_per_unit' => 400],
            ],
            'extra_fee' => 1000,
            'minimum_total' => 25000,
        ]);

        $calculator = $this->calculator();
        $product = $this->product();

        $atTen = $calculator->estimateValidated($product, $pricing, [7], 10);
        $this->assertSame(1800, $atTen['unit_price']);
        $this->assertSame(25000, $atTen['total']);
        $this->assertSame(6000, $atTen['breakdown']['minimum_total_adjustment']);
        $this->assertTrue($atTen['is_estimate']);

        $atTwenty = $calculator->estimateValidated($product, $pricing, [7], 20);
        $this->assertSame(1600, $atTwenty['unit_price']);
        $this->assertSame(33000, $atTwenty['total']);
        $this->assertSame($atTwenty, $calculator->estimateValidated($product, $pricing, [7], 20));
    }

    public function test_it_matches_matrix_ids_without_requiring_a_specific_order(): void
    {
        $pricing = $this->pricing([
            'matrix' => [['option_value_ids' => [8, 7], 'additional_price' => 750]],
        ]);

        $estimate = $this->calculator()->estimateValidated($this->product(), $pricing, [7, 8], 10);

        $this->assertSame(2250, $estimate['unit_price']);
        $this->assertSame(22500, $estimate['total']);
    }

    public function test_it_rejects_missing_matrix_rates_and_out_of_range_quantities(): void
    {
        $configs = [
            ['matrix' => [['option_value_ids' => [7, 8], 'additional_price' => 750]]],
            ['maximum_quantity' => 9],
            ['quantity_tiers' => [
                ['min' => 1, 'max' => 10, 'discount_per_unit' => 200],
                ['min' => 10, 'max' => 20, 'discount_per_unit' => 300],
            ]],
        ];

        foreach ($configs as $config) {
            try {
                $this->calculator()->estimateValidated($this->product(), $this->pricing($config), [7], 10);
                $this->fail('Harus gagal jika konfigurasi tarif tidak berlaku.');
            } catch (ValidationException $exception) {
                $this->assertArrayHasKey('pricing', $exception->errors());
            }
        }
    }

    private function product(): Product
    {
        return (new Product)->forceFill(['price_model' => 'calculator']);
    }

    private function pricing(array $config): PricingConfiguration
    {
        return (new PricingConfiguration)->forceFill([
            'price_model' => 'calculator',
            'base_price' => 1500,
            'config' => json_encode($config, JSON_THROW_ON_ERROR),
        ]);
    }

    private function calculator(): PriceCalculator
    {
        $catalog = new CatalogData;

        return new PriceCalculator($catalog, new ProductConfigurationValidator($catalog));
    }
}
