<?php

namespace Maatwebsite\Excel\Concerns {
    interface FromCollection {}
    interface WithHeadings {}
    interface WithMapping {}
    interface ShouldAutoSize {}
}

namespace Maatwebsite\Excel\Facades {
    class Excel
    {
        public static function download($export, string $fileName, string $writerType = null, array $headers = []) {}
    }
}

namespace Maatwebsite\Excel {
    class Excel
    {
        const XLSX = 'Xlsx';
        const CSV = 'Csv';
        const TSV = 'Tsv';
        const ODS = 'Ods';
        const XLS = 'Xls';
        const SLK = 'Slk';
        const XML = 'Xml';
        const GNUMERIC = 'Gnumeric';
        const HTML = 'Html';
        const MPDF = 'Mpdf';
        const TCPDF = 'Tcpdf';
        const DOMPDF = 'Dompdf';
    }
    class DefaultValueBinder {}
}

namespace Filament\Tables\Actions {
    class Action
    {
        public function label(string $label): static
        {
            return $this;
        }
        public function icon(string $icon): static
        {
            return $this;
        }
        public function color(string $color): static
        {
            return $this;
        }
        public function action($action): static
        {
            return $this;
        }
    }
    class BulkAction
    {
        public function label(string $label): static
        {
            return $this;
        }
        public function icon(string $icon): static
        {
            return $this;
        }
        public function color(string $color): static
        {
            return $this;
        }
        public function action($action): static
        {
            return $this;
        }
    }
    class ViewAction extends Action
    {
        public function label(string $label): static
        {
            return $this;
        }
        public function icon(string $icon): static
        {
            return $this;
        }
        public function color(string $color): static
        {
            return $this;
        }
        public function action($action): static
        {
            return $this;
        }
    }
    class EditAction extends Action
    {
        public function label(string $label): static
        {
            return $this;
        }
        public function icon(string $icon): static
        {
            return $this;
        }
        public function color(string $color): static
        {
            return $this;
        }
        public function action($action): static
        {
            return $this;
        }
    }
    class DeleteBulkAction extends BulkAction
    {
        public function label(string $label): static
        {
            return $this;
        }
        public function icon(string $icon): static
        {
            return $this;
        }
        public function color(string $color): static
        {
            return $this;
        }
        public function action($action): static
        {
            return $this;
        }
    }
}

namespace {
    /**
     * @return \App\Models\User|null
     */
    function auth() {}
}

namespace App\Models {
    class User
    {
        public function hasRole(string $role): bool
        {
            return true;
        }
    }
}

namespace Filament\Pages {

    use Illuminate\Contracts\Support\Htmlable;
    use Filament\Support\Enums\Alignment;

    class BasePage
    {
        protected static ?string $title = null;
        public static string | Alignment $formActionsAlignment = 'start';
    }

    class Page extends BasePage
    {
        protected static string|\BackedEnum|null $navigationIcon = null;
        protected static string|\UnitEnum|null $navigationGroup = null;
        protected static ?string $navigationLabel = null;
        protected static ?int $navigationSort = null;

        public static function getNavigationIcon(): string | Htmlable | null
        {
            return null;
        }
        public static function getNavigationGroup(): string | Htmlable | null
        {
            return null;
        }
    }
}
